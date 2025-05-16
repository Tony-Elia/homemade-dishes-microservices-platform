package DTO;

import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import models.Order;
import models.OrderItem;
import models.OrderStatus;
import models.ShippingCompany;

@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
public class OrderDTO {
	private Long id;
	private String userEmail;
	private Double totalAmount;
	private List<OrderItemDTO> items;
	private ShippingCompany shippingCompany;
	private OrderStatus status;
	private Date createdAt;
	
	public static OrderDTO from(Order o) {
		List<OrderItem> items = o.getItems();
		List<OrderItemDTO> newItems = new ArrayList<>();
		for(OrderItem i : items) {
			newItems.add(OrderItemDTO.fromWithoutOrder(i));
		}
		return new OrderDTO(o.getId(), o.getUserEmail(), o.getTotalAmount(), newItems, o.getShippingCompany(), o.getStatus(), o.getCreatedAt());
	}

	public static OrderDTO fromWithoutItems(Order o) {
		return new OrderDTO(o.getId(), o.getUserEmail(), o.getTotalAmount(), null, o.getShippingCompany(), o.getStatus(), o.getCreatedAt());
	}

	public Order toOrder() {
		return new Order(id, userEmail, totalAmount, null, shippingCompany, status, createdAt);
	}
}
