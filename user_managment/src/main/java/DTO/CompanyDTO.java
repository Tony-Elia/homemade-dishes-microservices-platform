package DTO;

import java.io.Serializable;
import models.Company;

public class CompanyDTO implements Serializable {

    private Long id;
    private String name;
    private String region;
    private UserDTO representative;

    public CompanyDTO() {
        // Required for JSON-B / JAX-RS
    }

    public CompanyDTO(Long id, String name, String region, UserDTO representative) {
        this.id = id;
        this.name = name;
        this.region = region;
        this.representative = representative;
    }

    public static CompanyDTO from(Company c) {
        return new CompanyDTO(
            c.getId(),
            c.getName(),
            c.getRegion(),
            c.getRepresentative() != null ? UserDTO.fromWithoutPassword(c.getRepresentative()) : null
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

    public String getRegion() {
        return region;
    }

    public void setRegion(String region) {
        this.region = region;
    }

    public UserDTO getRepresentative() {
        return representative;
    }

    public void setRepresentative(UserDTO representative) {
        this.representative = representative;
    }
}
