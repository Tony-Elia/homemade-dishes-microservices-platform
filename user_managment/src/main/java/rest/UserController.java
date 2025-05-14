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

import DTO.LoginRequest;
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
		UserDTO newUser = service.create(user, Role.SELLER_REPRESENTATIVE);
		return Response.status(newUser == null ? Status.BAD_REQUEST : Status.CREATED).entity(newUser).build();
	}
	
	@GET
	@Path("/{id:[0-9][0-9]*}")
	public Response findById(@PathParam("id") final Long id) {
		UserDTO user = service.findById(id);
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
		List<UserDTO> all = service.all(Role.SELLER_REPRESENTATIVE);
		System.out.println(all);
		return all;
	}

	@PUT
	@Path("/{id:[0-9][0-9]*}")
	public Response update(@PathParam("id") Long id, final User user) {
		UserDTO newUser = service.update(id, user);
		return Response.status(newUser != null ? Status.OK : Status.NOT_FOUND).entity(newUser).build();
	}

	@DELETE
	@Path("/{id:[0-9][0-9]*}")
	public Response deleteById(@PathParam("id") final Long id) {
		return Response.status(service.delete(id) ? Status.OK : Status.NOT_FOUND).entity("User Deleted Successfully!").build();
	}

}
