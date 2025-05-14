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
public class UsersSeeder {

	@Inject
    private EntityManager em;

    @PostConstruct
    public void seed() {
        if (em.createQuery("SELECT COUNT(u) FROM User u", Long.class).getSingleResult() == 0) {
            User admin = new User();
            admin.setName("Admin");
            admin.setRole(Role.ADMIN);
            admin.setUsername("admin");

            em.persist(admin);
        }
    }
}

