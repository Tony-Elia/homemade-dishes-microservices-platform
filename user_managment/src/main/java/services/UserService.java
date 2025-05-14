package services;

import java.util.ArrayList;
import java.util.List;

import javax.ejb.Stateless;
import javax.inject.Inject;
import javax.persistence.EntityManager;

import DTO.UserDTO;
import DTO.UserRequest;
import models.Company;
import models.Role;
import models.User;
import services.exceptions.ServiceException;

@Stateless
public class UserService {
	@Inject
	EntityManager em;

	public UserDTO create(UserRequest u, Role role) {
		if(usernameExist(u.getUsername()))
			throw new ServiceException("Username is already in use", 409);
		
		User newUser = new User();
		newUser.setRole(Role.CUSTOMER); // Force creation to Customers or Representatives only
		if(role == Role.SELLER_REPRESENTATIVE) {
			Company c = em.find(Company.class, u.getCompany_id());
			if(c == null) throw new ServiceException("Company ID Not Found", 404);
			if(c.getRepresentative() != null) throw new ServiceException("Company Has Already a Repesentative", 400);
			c.setRepresentative(newUser);
			newUser.setRole(Role.SELLER_REPRESENTATIVE);
		}
		
		newUser.setName(u.getName());
		newUser.setUsername(u.getUsername());
		em.persist(newUser);
		return UserDTO.from(newUser);
	}
	
	public boolean usernameExist(String username) {
		return em.createQuery("SELECT COUNT(u) from User u WHERE username = ?1", Long.class).setParameter(1, username).getSingleResult() > 0;
	}

	public UserDTO findById(Long id) {
		User user = em.find(User.class, id);
		return user != null ? UserDTO.from(user) : null;
	}

	public List<UserDTO> all(Role role) {
		List<User> users = em.createQuery("SELECT u FROM User u WHERE role = ?1", User.class)
				.setParameter(1, role).getResultList();
		List<UserDTO> resUsers = new ArrayList<>();
		
		for(User u : users) {
			resUsers.add(UserDTO.from(u));
		}
		return resUsers;
	}

	public UserDTO update(Long id, User u) {
		User user = em.find(User.class, id);
		if(user == null) return null;
		
		user.setName(u.getName());
		if(user.getRole() == Role.SELLER_REPRESENTATIVE)
			user.setCompany(u.getCompany());
		
		em.merge(user);
		return UserDTO.from(user);
	}

	public boolean delete(Long id) {
		User user = em.find(User.class, id);
		if(user == null) throw new ServiceException("User Not Found", 404);
		em.remove(user);
		return true;
	}
	
}
