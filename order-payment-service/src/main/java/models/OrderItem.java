package models;

import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.JoinColumn;
import javax.persistence.ManyToOne;

import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;

@Entity
@Setter
@Getter
@AllArgsConstructor
@NoArgsConstructor
public class OrderItem {
	@Id
	@GeneratedValue(strategy = GenerationType.IDENTITY)
	private Long id;
	@ManyToOne
	@JoinColumn(name = "order_id", nullable = false)
	private Order order;
	private Long dishId;
	private Integer quantity;
	private Double priceAtPurchase;
	private Long companyId;
	
	public boolean hasNullAttr() {
		return (dishId == null) || (quantity == null) || (priceAtPurchase == null);
	}
}
