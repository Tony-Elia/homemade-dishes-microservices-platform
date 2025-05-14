package DTO;

import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import models.Dish;

@Setter
@Getter
@NoArgsConstructor
@AllArgsConstructor
public class DishDTO {
	private Long id;
	private String name;
	private String description;
	private Integer calories;
	private Double price;
	private Long companyId;
	private Integer quantity;
	
	public static DishDTO from(Dish d) {
		return new DishDTO(d.getId(), d.getName(), d.getDescription(),
				d.getCalories(), d.getPrice(), d.getCompanyId(), d.getInventory().getQuantity());
	}

	public boolean hasNullAttr() {
		return (name.isEmpty() || description.isEmpty() || calories == null
				|| price == null || companyId == null || quantity == null);
	}
}
