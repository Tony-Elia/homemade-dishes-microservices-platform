package rest;

import java.util.List;

import javax.enterprise.context.RequestScoped;
import javax.inject.Inject;
import javax.ws.rs.Consumes;
import javax.ws.rs.DELETE;
import javax.ws.rs.GET;
import javax.ws.rs.HeaderParam;
import javax.ws.rs.POST;
import javax.ws.rs.PUT;
import javax.ws.rs.Path;
import javax.ws.rs.PathParam;
import javax.ws.rs.Produces;
import javax.ws.rs.QueryParam;
import javax.ws.rs.core.Response;
import javax.ws.rs.core.Response.Status;
import javax.ws.rs.core.Response.StatusType;

import DTO.DishDTO;
import models.Dish;
import services.DishService;

@RequestScoped
@Path("/dishes")
@Produces({ "application/json" })
@Consumes({ "application/json" })
public class DishController {
	@Inject
	DishService service;

	@POST
	public Response create(final DishDTO dish) {
		return Response.status(Status.CREATED).entity(service.create(dish)).build();
	}

	@GET
	public List<DishDTO> listAll(@HeaderParam("X-Company-Id") Long companyId) {
		return service.listAll(companyId);
	}

	@GET
	@Path("/{id:[0-9][0-9]*}")
	public DishDTO getById(@PathParam("id") Long dishId) {
		return service.getById(dishId);
	}
	
	@PUT
	@Path("/{id:[0-9][0-9]*}")
	public Response update(@HeaderParam("X-Company-Id") Long companyId, @PathParam("id") Long id, final DishDTO dish) {
		service.update(id, dish, companyId);
		return Response.ok("Updated Successfully").build();
	}

	@DELETE
	@Path("/{id:[0-9][0-9]*}")
	public Response deleteById(@HeaderParam("X-Company-Id")Long companyId, @PathParam("id") final Long id) {
		service.delete(id, companyId);
		return Response.ok("Dish Deleted Successfully").build();
	}

}
