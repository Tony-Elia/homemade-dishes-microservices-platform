package DTO;

import java.io.Serializable;
import models.Role;

public class UserRequest implements Serializable {

    private String name;
    private String username;
    private String password;
    private Role role;
    private Long company_id;

    public UserRequest() {
        // Required for JSON-B / JAX-RS
    }

    public UserRequest(String name, String username, String password, Role role, Long company_id) {
        this.name = name;
        this.username = username;
        this.password = password;
        this.role = role;
        this.company_id = company_id;
    }

    // Getters and setters
    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public Role getRole() {
        return role;
    }

    public void setRole(Role role) {
        this.role = role;
    }

    public Long getCompany_id() {
        return company_id;
    }

    public void setCompany_id(Long company_id) {
        this.company_id = company_id;
    }
}
