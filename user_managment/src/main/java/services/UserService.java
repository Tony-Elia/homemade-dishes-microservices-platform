package services;

import java.util.ArrayList;
import java.util.List;

import javax.ejb.Stateless;
import javax.ejb.TransactionAttribute;
import javax.ejb.TransactionAttributeType;
import javax.inject.Inject;
import javax.persistence.EntityManager;
import java.util.regex.Pattern;

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

    @TransactionAttribute(TransactionAttributeType.REQUIRED)
	public UserDTO create(UserRequest u, Role role) {
		if(emailExist(u.getEmail()))
			throw new ServiceException("Email is already in use", 409);
		
		if(!isValidEmail(u.getEmail()))
			throw new ServiceException("Email is not valid", 400);
		
		User newUser = new User();
		newUser.setRole(Role.CUSTOMER); // Force creation to Customers or Representatives only
		newUser.setName(u.getName());
		newUser.setEmail(u.getEmail());
		em.persist(newUser);
		
		if(role == Role.SELLER) {
			Company c = em.find(Company.class, u.getCompany_id());
			if(c == null) throw new ServiceException("Company ID Not Found", 404);
			if(c.getRepresentative() != null) throw new ServiceException("Company Has Already a Repesentative", 400);
			c.setRepresentative(newUser);
			newUser.setRole(Role.SELLER);
			
			em.merge(newUser);
			em.merge(c);
		}
		return UserDTO.from(newUser);
	}
	
	public boolean emailExist(String email) {
		return em.createQuery("SELECT COUNT(u) from User u WHERE email = ?1", Long.class).setParameter(1, email).getSingleResult() > 0;
	}

	public UserDTO findByEmail(String email) {
		User user = findEmail(email);
		return user != null ? UserDTO.from(user) : null;
	}
	
	private User findEmail(String email) {
		List<User> users = em.createQuery("SELECT u FROM User u WHERE email = ?1", User.class).setParameter(1, email).getResultList();
		return !users.isEmpty() ? users.get(0) : null;
	}
	
	private boolean isValidEmail(String email) {
		return Pattern.compile("^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$").matcher(email).matches();
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

    @TransactionAttribute(TransactionAttributeType.REQUIRED)
	public UserDTO update(String email, UserDTO u) {
		User user = findEmail(email);
		if(user == null) throw new ServiceException("User Not Found", 404);
		
		if(u.getEmail() != null) {
			if(!isValidEmail(u.getEmail()))
				throw new ServiceException("Email is not valid", 400);
			user.setEmail(u.getEmail());
		}
		
		if(u.getName() != null)
			user.setName(u.getName());
		
		if(user.getRole() == Role.SELLER && u.getCompany() != null) {
			if(user.getCompany() != null) user.getCompany().setRepresentative(null);
			Company c = em.find(Company.class, u.getCompany().getId());
			if(c == null) throw new ServiceException("Company ID Not Found", 404);
			if(c.getRepresentative() != null) throw new ServiceException("Company Has Already a Repesentative", 400);
			user.setCompany(c);
			c.setRepresentative(user);
		}
		
		em.merge(user);
		return UserDTO.from(user);
	}

	public boolean delete(String email) {
		User user = findEmail(email);
		if(user == null) throw new ServiceException("User Not Found", 404);
		em.remove(user);
		return true;
	}
}
