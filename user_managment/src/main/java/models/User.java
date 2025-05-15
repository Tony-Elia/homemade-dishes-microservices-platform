package models;

import javax.persistence.CascadeType;
import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.EnumType;
import javax.persistence.Enumerated;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.Index;
import javax.persistence.OneToOne;
import javax.persistence.Table;

@Entity
public class User {
	@Id
	@GeneratedValue(strategy = GenerationType.IDENTITY)
	private Long id;
	private String name;
	
	@Column(unique = true)
	private String email;
	
	@Enumerated(EnumType.STRING)
	private Role role;
	
	@OneToOne(mappedBy = "representative", cascade = CascadeType.ALL)
    private Company company;
	
	public Long getId() { return id; }
	public String getEmail() { return email; }
	public Role getRole() { return role; }
	public Company getCompany() {
		return company;
	}
	public void setEmail(String s) { email = s; }
	public void setRole(Role r) { role = r; }
	public void setCompany(Company c) { company = c; }
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
}