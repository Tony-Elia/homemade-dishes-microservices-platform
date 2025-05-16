package rest;

import java.util.List;

import javax.enterprise.context.RequestScoped;
import javax.inject.Inject;
import javax.ws.rs.Consumes;
import javax.ws.rs.GET;
import javax.ws.rs.POST;
import javax.ws.rs.Path;
import javax.ws.rs.PathParam;
import javax.ws.rs.Produces;
import javax.ws.rs.core.Response;

import DTO.OrderDTO;
import models.Order;
import models.ShippingCompany;
import services.OrderService;
import services.ShippingCompanyService;

@RequestScoped
@Path("/shipping-companies")
@Produces("application/json")
@Consumes("application/json")
public class ShippingCompanyController {
	@Inject
	ShippingCompanyService service;
	
	
	@GET
	public List<ShippingCompany> listAll() {
		return service.all();
	}
}
