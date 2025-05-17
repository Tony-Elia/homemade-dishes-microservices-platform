package DTO;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import models.OrderStatus;

@Getter
@Setter
@AllArgsConstructor
@NoArgsConstructor
public class CheckInventoryReply {
	private List<InventoryCheckItem> checkList = new ArrayList<>();
	private Long orderId;
	private OrderStatus status;
}
