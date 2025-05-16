package DB.seeders;

import javax.annotation.PostConstruct;
import javax.ejb.Singleton;
import javax.ejb.Startup;
import javax.inject.Inject;
import javax.persistence.EntityManager;
import javax.persistence.PersistenceContext;

import models.ShippingCompany;

@Singleton
@Startup
public class CompaniesSeeder {

    @Inject
    private EntityManager em;

    @PostConstruct
    public void seed() {
        if (em.createQuery("SELECT COUNT(u) FROM ShippingCompany u", Long.class).getSingleResult() == 0) {
        	ShippingCompany c = new ShippingCompany();
            c.setName("Talabat");
            c.setMinCharge(10.9);
            em.persist(c);
            
            ShippingCompany c1 = new ShippingCompany();
            c1.setName("FedEx");
            c1.setMinCharge(25.0);
            em.persist(c1);
            
            ShippingCompany c2 = new ShippingCompany();
            c2.setName("Aramex");
            c2.setMinCharge(20.2);
            em.persist(c2);
            
            ShippingCompany c3 = new ShippingCompany();
            c3.setName("El Zafer");
            c3.setMinCharge(5.0);
            em.persist(c3);
        }
    }
}

