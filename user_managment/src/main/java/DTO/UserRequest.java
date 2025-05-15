package DTO;

import java.io.Serializable;
import models.Role;

public class UserRequest implements Serializable {

    private String name;
    private String email;
    private Role role;
    private Long companyId;

    public UserRequest() {
        // Required for JSON-B / JAX-RS
    }

    public UserRequest(String name, String email, Role role, Long companyId) {
        this.name = name;
        this.email = email;
        this.role = role;
        this.companyId = companyId;
    }

    // Getters and setters
    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String username) {
        this.email = username;
    }

    public Role getRole() {
        return role;
    }

    public void setRole(Role role) {
        this.role = role;
    }

    public Long getCompany_id() {
        return companyId;
    }

    public void setCompany_id(Long companyId) {
        this.companyId = companyId;
    }
}
