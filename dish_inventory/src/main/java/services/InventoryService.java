package services;

import java.util.ArrayList;
import java.util.List;

import javax.ejb.Stateless;
import javax.ejb.TransactionAttribute;
import javax.ejb.TransactionAttributeType;
import javax.inject.Inject;
import javax.persistence.EntityManager;

import com.fasterxml.jackson.core.JsonProcessingException;
import com.fasterxml.jackson.databind.JsonMappingException;
import com.fasterxml.jackson.databind.ObjectMapper;

import DTO.CheckInventoryReply;
import DTO.CheckInventoryRequest;
import DTO.InventoryCheckItem;
import models.Inventory;
import models.OrderStatus;
import mq.OrderConfirmationMQ;
import services.exceptions.ServiceException;

@Stateless
public class InventoryService {
	@Inject
	EntityManager em;
	
	@Inject
	OrderConfirmationMQ confirmMq;
	
	@TransactionAttribute(TransactionAttributeType.REQUIRED)
	public void checkInventory(CheckInventoryRequest req) {
		
		if(req == null)
			throw new ServiceException("Cannot Pasre the check request", 400);
		
		for(InventoryCheckItem i : req.getCheckList()) {
			List<Inventory> inv = em.createQuery("SELECT i FROM Inventory i WHERE dish_id = ?1", Inventory.class).setParameter(1, i.getDishId()).getResultList();
			if(inv.get(0) == null) throw new ServiceException("dishId: " + i.getDishId() + "Not Found", 404);
			
			if(inv.get(0).getQuantity() < i.getQuantity()) throw new ServiceException("dishId: " + i.getDishId() + " quantity is less than the available", 400);
			inv.get(0).setQuantity(inv.get(0).getQuantity() - i.getQuantity());
			em.merge(inv.get(0));
			
			i.setCompanyId(inv.get(0).getDish().getCompanyId());
		}
	}
	
	private CheckInventoryRequest deserializeInventoryRequest(String msg) {
		ObjectMapper mapper = new ObjectMapper();
		try {
			return mapper.readValue(msg, CheckInventoryRequest.class);
			
		} catch (JsonMappingException e) {
			e.printStackTrace();
		} catch (JsonProcessingException e) {
			e.printStackTrace();
		}
		return null;
	}

	@TransactionAttribute(TransactionAttributeType.REQUIRED)
	public void handleInventoryCheck(String message) throws JsonProcessingException {
		CheckInventoryReply reply = new CheckInventoryReply();
		ObjectMapper obj = new ObjectMapper();
		reply.setStatus(OrderStatus.REQUESTED);
		try {
			CheckInventoryRequest req = deserializeInventoryRequest(message);
			reply.setCheckList(req.getCheckList());
			reply.setOrderId(req.getOrderId());
			checkInventory(req);
			reply.setStatus(OrderStatus.UNPAID);
			
			confirmMq.sendConfirmation(obj.writeValueAsString(reply));
		} catch (Exception e) {
			confirmMq.sendConfirmation(obj.writeValueAsString(reply));
		}
		
	}
}
