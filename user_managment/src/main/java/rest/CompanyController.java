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
import javax.ws.rs.core.Response.Status;
import javax.ws.rs.core.UriBuilder;

import DTO.CompanyDTO;
import models.Company;
import services.CompanyService;

@RequestScoped
@Path("/companies")
@Produces({ "application/json" })
@Consumes({ "application/json" })
public class CompanyController {

	@Inject
	CompanyService service;
	
	@POST
	public Response create(final Company company) {
		CompanyDTO comp = service.create(company);
		return Response.status(comp != null ? Status.CREATED : Status.BAD_REQUEST).entity(comp).build();
	}

	@GET
	@Path("/{id:[0-9][0-9]*}")
	public Response findById(@PathParam("id") final Long id) {
		CompanyDTO company = service.findById(id);
		if (company == null) {
			return Response.status(Status.NOT_FOUND).entity("Company Not Found").build();
		}
		return Response.ok(company).build();
	}

	@GET
	public List<CompanyDTO> listAll() {
		return service.all();
	}

}
