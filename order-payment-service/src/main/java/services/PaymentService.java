package services;

import javax.ejb.Stateless;
import javax.ejb.TransactionAttribute;
import javax.ejb.TransactionAttributeType;
import javax.inject.Inject;
import javax.persistence.EntityManager;
import models.Order;
import models.OrderStatus;
import services.exceptions.ServiceException;

@Stateless
public class PaymentService {
	@Inject
	EntityManager em;
	@TransactionAttribute(TransactionAttributeType.NOT_SUPPORTED)
	public void pay(Long orderId) {
		Order order = em.find(Order.class, orderId);
		
		if(order == null) throw new ServiceException("Order not Found", 404);
		if(order.getShippingCompany().getMinCharge() > order.getTotalAmount()) {
			em.remove(order);
			throw new ServiceException("Minimum charge is not met in the order", 400);
		} else {
			order.setStatus(OrderStatus.COMPLETED);
			em.merge(order);			
		}
	}
}
