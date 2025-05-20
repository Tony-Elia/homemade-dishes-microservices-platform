package services;

import java.util.ArrayList;
import java.util.List;

import javax.ejb.Stateless;
import javax.ejb.TransactionAttribute;
import javax.ejb.TransactionAttributeType;
import javax.inject.Inject;
import javax.persistence.EntityManager;
import javax.ws.rs.client.Client;
import javax.ws.rs.client.ClientBuilder;
import javax.ws.rs.client.Entity;
import javax.ws.rs.client.WebTarget;
import javax.ws.rs.core.MediaType;
import javax.ws.rs.core.Response;

import com.fasterxml.jackson.core.JsonProcessingException;
import com.fasterxml.jackson.databind.JsonMappingException;
import com.fasterxml.jackson.databind.ObjectMapper;

import models.Order;
import models.OrderItem;
import models.OrderStatus;
import models.ShippingCompany;
import mq.InventoryCheckMQ;
import DTO.CheckInventoryReply;
import DTO.CheckInventoryRequest;
import DTO.InventoryCheckItem;
import DTO.OrderDTO;
import DTO.OrderStatusUpdate;
import services.exceptions.ServiceException;

@Stateless
public class OrderService {
	@Inject
	EntityManager em;
	
	@Inject
	InventoryCheckMQ mq;
	

	@TransactionAttribute(TransactionAttributeType.REQUIRED)
	public OrderDTO create(Order order) {
		if(order.hasNullAttr()) throw new ServiceException("Missing some attributes", 400);
		
		
		List<OrderItem> items = order.getItems();
		order.setItems(null);
		Double total = 0D;
		
		for(OrderItem item : items) {
			if(item.hasNullAttr()) throw new ServiceException("Missing some attributes", 400);
			if(item.getQuantity() < 0) throw new ServiceException("Quantity cannot be a negative number", 400);
			if(item.getPriceAtPurchase() < 0) throw new ServiceException("Price At Purchase cannot be a negative number", 400);
			
			item.setOrder(order);
			total += item.getPriceAtPurchase() * item.getQuantity();
		}
		
		ShippingCompany shippingCompany = findShippinCompanyById(order.getShippingCompany().getId());
		if(shippingCompany.getMinCharge() > total) throw new ServiceException("Total order amount is below the chosen Shipping company's minimum charge", 400);
		order.setShippingCompany(shippingCompany);
		order.setTotalAmount(total);
		order.setStatus(OrderStatus.REQUESTED);
		em.persist(order);
		
		for(OrderItem i : items) {
			em.persist(i);
		}
		
		em.refresh(order);
		checkInventory(order);
		return OrderDTO.from(order);
	}


	public List<OrderDTO> all(String userEmail) {
		List<Order> orders = em.createQuery("SELECT o FROM Order o WHERE userEmail = ?1", Order.class).setParameter(1, userEmail).getResultList();
		List<OrderDTO> newOrders = new ArrayList<>();
		
		for(Order o : orders) {
			newOrders.add(OrderDTO.from(o));
		}
		return newOrders;
	}
	
	public List<OrderDTO> all(Long companyId) {
		List<OrderItem> orders = em.createQuery("SELECT i FROM OrderItem i WHERE companyId = ?1", OrderItem.class).setParameter(1, companyId).getResultList();
		List<OrderDTO> newOrders = new ArrayList<>();
		
		for(OrderItem item : orders) {
			Order order = item.getOrder();
			if(order.getStatus() == OrderStatus.COMPLETED)
				newOrders.add(OrderDTO.from(order));
		}
		return newOrders;
	}
	
	public ShippingCompany findShippinCompanyById(Long id) {
		return em.find(ShippingCompany.class, id);
	}
	
	public void checkInventory(Order o) {
		ObjectMapper mapper = new ObjectMapper();
		String json;
		try {
			json = mapper.writeValueAsString(serliazeCheckInventoryRequest(o));
			mq.sendInventoryCheck(json);
		} catch (JsonProcessingException e) {
			e.printStackTrace();
		}
	}
	
	public CheckInventoryRequest serliazeCheckInventoryRequest(Order o) {
		CheckInventoryRequest req = new CheckInventoryRequest();
		
		for(OrderItem item : o.getItems()) {
			req.addItem(item.getDishId(), item.getQuantity());
		}
		req.setOrderId(o.getId());
		return req;
	}
	
	public CheckInventoryReply deserliazeCheckInventoryReply(String msg) {
		ObjectMapper mapper = new ObjectMapper();
		try {
			return mapper.readValue(msg, CheckInventoryReply.class);
			
		} catch (JsonMappingException e) {
			e.printStackTrace();
		} catch (JsonProcessingException e) {
			e.printStackTrace();
		}
		return null;
	}

	@TransactionAttribute(TransactionAttributeType.REQUIRED)
	public void updateCompanyIdForOrder(Long orderId, List<InventoryCheckItem> items) {		
		for(InventoryCheckItem item : items) {
			em.createQuery("UPDATE OrderItem oi SET oi.companyId = :companyId WHERE oi.dishId = :dishId AND order_id = :orderId")
			.setParameter("companyId", item.getCompanyId())
			.setParameter("orderId", orderId)
			.setParameter("dishId", item.getDishId()).executeUpdate();
		}
	}

	public void sendOrderStatusUpdate(Long orderId, String userEmail, String msg, boolean status) {
	    OrderStatusUpdate payload = new OrderStatusUpdate(orderId, msg, status, userEmail);
	    

	    Client client = ClientBuilder.newClient();
	    WebTarget target = client.target("http://localhost:8000/api/orders/status");

	    Response response = target
	        .request(MediaType.APPLICATION_JSON)
	        .put(Entity.entity(payload, MediaType.APPLICATION_JSON));

	    if (response.getStatus() == 200 || response.getStatus() == 204) {
	        System.out.println(">>>> Order status updated successfully.");
	    } else {
	        System.err.println(">>>> Failed to update order status: " + response.getStatus());
	    }

	    response.close();
	    client.close();
	}

	
	public void orderConfirmation(String payloadJson) {
		CheckInventoryReply reply = deserliazeCheckInventoryReply(payloadJson);
		Order order = em.find(Order.class, reply.getOrderId());
		order.setStatus(reply.getStatus());
		String s = "Order is Accpted";
		boolean f = true;
		if(reply.getStatus() != OrderStatus.REQUESTED) {
			updateCompanyIdForOrder(reply.getOrderId(), reply.getCheckList());
			em.merge(order);
		} else {
			em.remove(order);
			s = "Not enough stock for the ordered items\nOrder Cancelled";
			f = false;
		}
		
		sendOrderStatusUpdate(order.getId(), order.getUserEmail(), s, f);
	}
}
