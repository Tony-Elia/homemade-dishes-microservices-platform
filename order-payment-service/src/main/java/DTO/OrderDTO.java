package DTO;

import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import javax.persistence.CascadeType;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.JoinColumn;
import javax.persistence.ManyToOne;
import javax.persistence.OneToMany;

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
public class OrderDTO {
	private Long id;
	private Long userId;
	private Double totalAmount;
	private List<OrderItemDTO> items;
	private Date createdAt;
	
	public static OrderDTO from(Order o) {
		List<OrderItem> items = o.getItems();
		List<OrderItemDTO> newItems = new ArrayList<>();
		for(OrderItem i : items) {
			newItems.add(OrderItemDTO.fromWithoutOrder(i));
		}
		return new OrderDTO(o.getId(), o.getUserId(), o.getTotalAmount(), newItems, o.getCreatedAt());
	}

	public static OrderDTO fromWithoutItems(Order o) {
		return new OrderDTO(o.getId(), o.getUserId(), o.getTotalAmount(), null, o.getCreatedAt());
	}
}
