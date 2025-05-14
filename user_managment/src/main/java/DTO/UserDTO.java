package DTO;

import java.io.Serializable;

import models.Role;
import models.User;

public class UserDTO implements Serializable {

    private Long id;
    private String name;
    private String username;
    private Role role;

    public UserDTO() {
        // Required for frameworks like Jackson or JSON-B
    }

    public UserDTO(Long id, String name, String username, Role role) {
        this.id = id;
        this.name = name;
        this.username = username;
        this.role = role;
    }

    public static UserDTO from(User user) {
        return new UserDTO(
            user.getId(),
            user.getName(),
            user.getUsername(),
            user.getRole()
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

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public Role getRole() {
        return role;
    }

    public void setRole(Role role) {
        this.role = role;
    }
}
