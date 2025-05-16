package DTO;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;

@Getter
@Setter
@AllArgsConstructor
@NoArgsConstructor
public class CheckInventoryRequest {
	private List<InventoryCheckItem> checkList = new ArrayList<>();
	private Long orderId;

	public void addItem(Long dishId, Integer quantity) {
		checkList.add(new InventoryCheckItem(dishId, quantity, null));
	}
}

