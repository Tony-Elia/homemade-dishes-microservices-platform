package rest;

import javax.enterprise.context.RequestScoped;
import javax.inject.Inject;
import javax.ws.rs.Consumes;
import javax.ws.rs.GET;
import javax.ws.rs.Path;
import javax.ws.rs.PathParam;
import javax.ws.rs.Produces;
import javax.ws.rs.core.Response;
import services.PaymentService;

@RequestScoped
@Path("/pay")
@Produces("application/json")
@Consumes("application/json")
public class PaymentController {
	@Inject
	PaymentService service;

	@GET
	@Path("/{id:[0-9]+}")
	public Response pay(@PathParam("id") Long orderId) {
		service.pay(orderId);
		return Response.ok("Order Paid successfully!").build();
	}
}
