package DTO;

import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.JoinColumn;
import javax.persistence.ManyToOne;

import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import models.Order;
import models.OrderItem;

@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
public class OrderItemDTO {
	private Long id;
	private OrderDTO order;
	private Long dishId;
	private Integer quantity;
	private Double priceAtPurchase;
	private Long companyId;
	
	
	public static OrderItemDTO from(OrderItem i) {
		return new OrderItemDTO(i.getId(), OrderDTO.fromWithoutItems(i.getOrder()), i.getDishId(), i.getQuantity(), i.getPriceAtPurchase(), i.getCompanyId());
	}
	public static OrderItemDTO fromWithoutOrder(OrderItem i) {
		return new OrderItemDTO(i.getId(), null, i.getDishId(), i.getQuantity(), i.getPriceAtPurchase(), i.getCompanyId());
	}
}
