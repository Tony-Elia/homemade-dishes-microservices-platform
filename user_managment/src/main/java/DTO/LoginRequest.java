package DTO;

import java.io.Serializable;

@SuppressWarnings("serial")
public class LoginRequest implements Serializable {

    private String username;
    private String password;

    public LoginRequest() {
        // Required no-arg constructor for JSON-B / Jackson
    }

    public LoginRequest(String username, String password) {
        this.username = username;
        this.password = password;
    }

    // Getters and setters
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
}
