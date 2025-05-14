package models;

import javax.persistence.CascadeType;
import javax.persistence.Entity;
import javax.persistence.EnumType;
import javax.persistence.Enumerated;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.OneToOne;

@Entity
public class User {
	@Id
	@GeneratedValue(strategy = GenerationType.IDENTITY)
	private Long id;
	private String name;
	private String username;
	
	@Enumerated(EnumType.STRING)
	private Role role;
	
	@OneToOne(mappedBy = "representative", cascade = CascadeType.ALL)
    private Company company;
	
	public Long getId() { return id; }
	public String getUsername() { return username; }
	public Role getRole() { return role; }
	public Company getCompany() {
		return company;
	}
	public void setUsername(String s) { username = s; }
	public void setRole(Role r) { role = r; }
	public void setCompany(Company c) { company = c; }
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
}