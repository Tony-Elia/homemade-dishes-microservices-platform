package rest;

import java.util.List;

import javax.enterprise.context.RequestScoped;
import javax.inject.Inject;
import javax.ws.rs.Consumes;
import javax.ws.rs.GET;
import javax.ws.rs.HeaderParam;
import javax.ws.rs.POST;
import javax.ws.rs.Path;
import javax.ws.rs.PathParam;
import javax.ws.rs.Produces;
import javax.ws.rs.core.Response;

import DTO.OrderDTO;
import models.Order;
import services.OrderService;
import services.PaymentService;

@RequestScoped
@Path("/orders")
@Produces("application/json")
@Consumes("application/json")
public class OrderController {
	@Inject
	OrderService service;
	
	@Inject
	PaymentService paymentService;

	@POST
	public Response create(final Order order) {
		return Response.ok(service.create(order)).build();
	}

	@GET
	@Path("/{email}")
	public List<OrderDTO> listAll(@PathParam("email") String userEmail) {
		return service.all(userEmail);
	}
	
	@GET
	public List<OrderDTO> listAll(@HeaderParam("X-Company-Id") Long companyId) {
		return service.all(companyId);
	}
}
