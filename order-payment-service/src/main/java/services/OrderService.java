package services;

import java.util.ArrayList;
import java.util.List;

import javax.ejb.Stateless;
import javax.ejb.TransactionAttribute;
import javax.ejb.TransactionAttributeType;
import javax.inject.Inject;
import javax.persistence.EntityManager;

import models.Order;
import models.OrderItem;
import models.OrderStatus;
import models.ShippingCompany;
import DTO.OrderDTO;
import services.exceptions.ServiceException;

@Stateless
public class OrderService {
	@Inject
	EntityManager em;
	

	@TransactionAttribute(TransactionAttributeType.REQUIRED)
	public OrderDTO create(Order order) {
		if(order.hasNullAttr()) throw new ServiceException("Missing some attributes", 400);
		
		order.setShippingCompany(findShippinCompanyById(order.getShippingCompany().getId()));
		
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
		
		order.setTotalAmount(total);
		order.setStatus(OrderStatus.REQUESTED);
		em.persist(order);
		
		for(OrderItem i : items) {
			em.persist(i);
		}
		
		em.refresh(order);
		return OrderDTO.from(order);
	}


	public List<OrderDTO> all(Long userId) {
		List<Order> orders = em.createQuery("SELECT o FROM Order o WHERE userId = ?1", Order.class).setParameter(1, userId).getResultList();
		List<OrderDTO> newOrders = new ArrayList<>();
		
		for(Order o : orders) {
			newOrders.add(OrderDTO.from(o));
		}
		return newOrders;
	}
	
	public ShippingCompany findShippinCompanyById(Long id) {
		return em.find(ShippingCompany.class, id);
	}
}
