package services;

import java.util.ArrayList;
import java.util.List;

import javax.ejb.Stateless;
import javax.ejb.TransactionAttribute;
import javax.ejb.TransactionAttributeType;
import javax.inject.Inject;
import javax.persistence.EntityManager;

import DTO.DishDTO;
import models.Dish;
import models.Inventory;
import services.exceptions.ServiceException;

@Stateless
public class DishService {
	@Inject
	EntityManager em;
	
	public void checkOwnership(Dish d, Long passedId) {
		if(!d.getCompanyId().equals(passedId)) throw new ServiceException("Forbidden: You Don't Own This Resource", 403);
	}
	
    @TransactionAttribute(TransactionAttributeType.REQUIRED)
	public DishDTO create(DishDTO d) {
		if(d.hasNullAttr()) throw new ServiceException("Missing Some Dish Attributes", 400);
		
		Dish newDish = new Dish();
		
		newDish.setName(d.getName());
		newDish.setPrice(d.getPrice());
		newDish.setDescription(d.getDescription());
		newDish.setCompanyId(d.getCompanyId());
		newDish.setCalories(d.getCalories());
		em.persist(newDish);
		
		if(d.getQuantity() < 0) throw new ServiceException("Quantity Cannot Be a Negative Number", 400);
		Inventory inventory = new Inventory();
		inventory.setDish(newDish);
		inventory.setQuantity(d.getQuantity());
		em.persist(inventory);
		newDish.setInventory(inventory);
		em.merge(newDish);
		
		return DishDTO.from(newDish);
	}

	public List<DishDTO> listAll(Long companyId) {
		List<Dish> dishes = em.createQuery("SELECT d FROM Dish d WHERE companyId = ?1", Dish.class).setParameter(1, companyId).getResultList();
		
		List<DishDTO> newDishes = new ArrayList<>();
		for(Dish d : dishes) {
			newDishes.add(DishDTO.from(d));
		}
		return newDishes;
	}

    @TransactionAttribute(TransactionAttributeType.REQUIRED)
	public void update(Long id, DishDTO dish, Long companyId) {
		Dish newDish = em.find(Dish.class, id);
		if(newDish == null) throw new ServiceException("Dish Not Found", 404);
		checkOwnership(newDish, companyId);
		
		newDish.setName(dish.getName());
		newDish.setPrice(dish.getPrice());
		newDish.setDescription(dish.getDescription());
		newDish.setCalories(dish.getCalories());
		
		if(dish.getQuantity() < 0) throw new ServiceException("Quantity Cannot Be a Negative Number", 400);
		newDish.getInventory().setQuantity(dish.getQuantity());
	}

	public void delete(Long id, Long companyId) {
		Dish d = em.find(Dish.class, id);
		if(d == null) throw new ServiceException("Dish Not Found", 404);
		checkOwnership(d, companyId);
		em.remove(d);
	}

	public DishDTO getById(Long dishId) {
		Dish dish = em.find(Dish.class, dishId);
		if(dish == null) throw new ServiceException("Dish Not Found", 404);
		return DishDTO.from(dish);
	}
}
