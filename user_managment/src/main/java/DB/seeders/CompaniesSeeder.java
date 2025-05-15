package DB.seeders;

import javax.annotation.PostConstruct;
import javax.ejb.Singleton;
import javax.ejb.Startup;
import javax.inject.Inject;
import javax.persistence.EntityManager;
import javax.persistence.PersistenceContext;

import models.Company;
import models.Role;
import models.User;

@Singleton
@Startup
public class CompaniesSeeder {

    @Inject
    private EntityManager em;

    @PostConstruct
    public void seed() {
        if (em.createQuery("SELECT COUNT(u) FROM Company u", Long.class).getSingleResult() == 0) {
        	User rep = new User();
        	rep.setName("Ahmed 3");
        	rep.setRole(Role.SELLER);
        	rep.setEmail("ahmed_rep_3");
        	em.persist(rep);
        	
        	Company c = new Company();
            c.setName("Microhard");
            c.setRegion("Africa/Cairo");
            em.persist(c);
        	
        	Company c2 = new Company();
            c2.setName("El Etihad");
            c2.setRegion("US/Chicago");
            em.persist(c2);
            
            Company c3 = new Company();
            c3.setName("EL Azarita Co.");
            c3.setRegion("Eurpoe/Paris");
            em.persist(c3);
        }
    }
}

