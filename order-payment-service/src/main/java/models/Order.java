package models;

import java.util.Date;
import java.util.List;

import javax.persistence.CascadeType;
import javax.persistence.Entity;
import javax.persistence.EnumType;
import javax.persistence.Enumerated;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.JoinColumn;
import javax.persistence.ManyToOne;
import javax.persistence.OneToMany;
import javax.persistence.PrePersist;
import javax.persistence.Table;
import javax.persistence.Temporal;
import javax.persistence.TemporalType;

import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;

@Entity
@Setter
@Getter
@AllArgsConstructor
@NoArgsConstructor
@Table(name = "`Order`")
public class Order {
	@Id
	@GeneratedValue(strategy = GenerationType.IDENTITY)
	private Long id;
	private String userEmail;
	private Double totalAmount;
	@OneToMany(mappedBy = "order", cascade = CascadeType.ALL)
	private List<OrderItem> items;
	
	@ManyToOne
	@JoinColumn(name = "shipping_company_id")
	private ShippingCompany shippingCompany;
	
	@Enumerated(EnumType.STRING)
	private OrderStatus status;
	
	@Temporal(TemporalType.TIMESTAMP)
	private Date createdAt;

	@PrePersist
	protected void onCreate() {
	    this.createdAt = new Date();
	}
	
	public boolean hasNullAttr() {
		return (userEmail == null) || ( items == null) || items.isEmpty() || (shippingCompany == null) || (shippingCompany.getId() == null);
	}
	
	public void setShippingCompanyId(Long id) {
		shippingCompany = new ShippingCompany(id, null, null);
	}
}
