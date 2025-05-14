package services;

import java.util.ArrayList;
import java.util.List;

import javax.ejb.Stateless;
import javax.ejb.TransactionAttribute;
import javax.ejb.TransactionAttributeType;
import javax.inject.Inject;
import javax.persistence.EntityManager;

import DTO.CompanyDTO;
import models.Company;
import models.User;
import services.exceptions.ServiceException;

@Stateless
public class CompanyService {
	@Inject
	EntityManager em;

    @TransactionAttribute(TransactionAttributeType.REQUIRED)
	public CompanyDTO create(Company company) {
		if(company.getName().isEmpty())
			throw new ServiceException("Company Name cannot be null", 400);

		if(nameExist(company.getName()))
			throw new ServiceException("Company Name is already in use", 409);
		

		em.persist(company);
		return CompanyDTO.from(company);
	}
	
	public boolean nameExist(String name) {
		return em.createQuery("SELECT COUNT(u) from Company u WHERE name = ?1", Long.class)
				.setParameter(1, name).getSingleResult() > 0;
	}

	public CompanyDTO findById(Long id) {
		Company company = em.find(Company.class, id);
		return company != null ? CompanyDTO.from(company) : null;
	}

	public List<CompanyDTO> all() {
		List<Company> companies = em.createQuery("SELECT u FROM Company u", Company.class)
				.getResultList();
		List<CompanyDTO> resCompanies = new ArrayList<>();
		
		for(Company u : companies) {
			resCompanies.add(CompanyDTO.from(u));
		}
		return resCompanies;
	}

    @TransactionAttribute(TransactionAttributeType.REQUIRED)
	public boolean attachRepresentative(Long id, Long rep_id) {
		Company company = em.find(Company.class, id);
		User rep = em.find(User.class, rep_id);
		if(company == null || rep == null) return false;
		
		company.setRepresentative(rep);
		
		em.merge(company);
		return true;
	}
}
