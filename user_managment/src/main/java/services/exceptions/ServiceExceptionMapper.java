package services.exceptions;

import javax.json.Json;
import javax.json.JsonObject;
import javax.ws.rs.core.Response;
import javax.ws.rs.ext.Provider;
import javax.ws.rs.ext.ExceptionMapper;

@Provider
public class ServiceExceptionMapper implements ExceptionMapper<ServiceException> {
	@Override
    public Response toResponse(ServiceException exception) {
        JsonObject errorJson = Json.createObjectBuilder()
            .add("error", exception.getMessage())
            .add("status", exception.getStatusCode())
            .build();

        return Response.status(exception.getStatusCode())
                       .entity(errorJson)
                       .build();
    }
}
