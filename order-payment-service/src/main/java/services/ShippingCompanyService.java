package services;

import java.util.List;

import javax.ejb.Stateless;
import javax.inject.Inject;
import javax.persistence.EntityManager;

import models.ShippingCompany;

@Stateless
public class ShippingCompanyService {
	@Inject
	EntityManager em;
	
	public List<ShippingCompany> all() {
		return em.createQuery("SELECT s FROM ShippingCompany s", ShippingCompany.class).getResultList();
	}
}
