package rest;

import java.util.List;

import javax.enterprise.context.RequestScoped;
import javax.inject.Inject;
import javax.ws.rs.Consumes;
import javax.ws.rs.DELETE;
import javax.ws.rs.GET;
import javax.ws.rs.POST;
import javax.ws.rs.PUT;
import javax.ws.rs.Path;
import javax.ws.rs.PathParam;
import javax.ws.rs.Produces;
import javax.ws.rs.core.Response;
import javax.ws.rs.core.Response.Status;

import DTO.UserDTO;
import DTO.UserRequest;
import models.Role;
import models.User;
import rest.filters.AllowedRoles;
import services.UserService;

@RequestScoped
@Path("/users")
@Produces({ "application/json" })
@Consumes({ "application/json" })
public class UserController {
	@Inject
	private UserService service;

	@POST
	@Path("/register/customer")
	@AllowedRoles({Role.CUSTOMER, Role.ADMIN})
	public Response createCustomer(final UserRequest user) {
		UserDTO newUser = service.create(user, Role.CUSTOMER);
		return Response.status(newUser == null ? Status.BAD_REQUEST : Status.CREATED).entity(newUser).build();
	}
	
	@POST
	@Path("/register/representative")
	@AllowedRoles({Role.ADMIN})
	public Response createRepresentative(final UserRequest user) {
		UserDTO newUser = service.create(user, Role.SELLER);
		return Response.status(newUser == null ? Status.BAD_REQUEST : Status.CREATED).entity(newUser).build();
	}
	
	@GET
	@Path("/{email}")
	public Response findById(@PathParam("email") final String email) {
		UserDTO user = service.findByEmail(email);
		if (user == null) {
			return Response.status(Status.NOT_FOUND).build();
		}
		return Response.ok(user).build();
	}

	@GET
	@Path("/customers")
	@AllowedRoles({Role.ADMIN})
	public List<UserDTO> listAllCustomers() {
		return service.all(Role.CUSTOMER);
	}
	
	@GET
	@Path("/seller-representatives")
	@AllowedRoles({Role.ADMIN})
	public List<UserDTO> listAllRepresentatives() {
		return service.all(Role.SELLER);
	}

	@PUT
	@Path("/{email}")
	public Response update(@PathParam("email") String email, final UserDTO user) {
		return Response.ok(service.update(email, user)).build();
	}

	@DELETE
	@Path("/{email}")
	public Response deleteById(@PathParam("email") final String email) {
		boolean deleted = service.delete(email);
		return Response.status(deleted ? Status.OK : Status.NOT_FOUND).entity(deleted ? "User Deleted Successfully!" : "User Not Found!").build();
	}

}
