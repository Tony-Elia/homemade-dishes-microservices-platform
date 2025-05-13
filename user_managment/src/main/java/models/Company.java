package models;

import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.JoinColumn;
import javax.persistence.OneToOne;

@Entity
public class Company {
	@Id
	@GeneratedValue(strategy = GenerationType.IDENTITY)
	private Long id;
	private String name;
	private String region;
	
	@OneToOne
    @JoinColumn(name = "representative_id", nullable = true)
    private User representative;

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}
	
	public User getRepresentative() {
		return representative;
	}
	
	public void setRepresentative(User user) {
		representative = user;
	}
	
	public Long getId() { return id; }
	public void setId(Long i) { id = i; }

	public String getRegion() {
		return region;
	}

	public void setRegion(String region) {
		this.region = region;
	}
}
