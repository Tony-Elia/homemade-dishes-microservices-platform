package DTO;

import java.io.Serializable;

import models.Company;
import models.Role;
import models.User;

public class UserDTO implements Serializable {

    private Long id;
    private String name;
    private String email;
    private Role role;
    private CompanyDTO company;

    public UserDTO() {
        // Required for frameworks like Jackson or JSON-B
    }

    public UserDTO(Long id, String name, String email, Role role, Company c) {
        this.id = id;
        this.name = name;
        this.email = email;
        this.role = role;
        this.company = c != null ? new CompanyDTO(c.getId(), c.getName(), c.getRegion(), null) : null;
    }

    public static UserDTO from(User user) {
        return new UserDTO(
            user.getId(),
            user.getName(),
            user.getEmail(),
            user.getRole(),
            (user.getRole() == Role.SELLER) ? user.getCompany() : null
        );
    }

    // Getters and setters
    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public Role getRole() {
        return role;
    }

    public void setRole(Role role) {
        this.role = role;
    }

	public CompanyDTO getCompany() {
		return company;
	}
	
	public void setCompany(CompanyDTO c) {
		this.company = c;
	}
	
	public void setCompany_id(Long id) {
		this.company = new CompanyDTO(id, null, null, null);
	}
}
