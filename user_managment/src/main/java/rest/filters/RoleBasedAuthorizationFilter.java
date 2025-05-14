package rest.filters;
import javax.ws.rs.container.ContainerRequestContext;
import javax.ws.rs.container.ContainerRequestFilter;
import javax.ws.rs.container.ResourceInfo;
import javax.ws.rs.core.Context;
import javax.ws.rs.core.HttpHeaders;
import javax.ws.rs.core.Response;
import javax.ws.rs.ext.Provider;

import models.Role;

import java.io.IOException;
import java.lang.annotation.Annotation;
import java.lang.reflect.Method;

@Provider
public class RoleBasedAuthorizationFilter implements ContainerRequestFilter {

    @Context
    private HttpHeaders httpHeaders;
    
    @Context
    private ResourceInfo resource;

    @Override
    public void filter(ContainerRequestContext requestContext) throws IOException {

        // Get the target method being invoked (the resource method)
        Method method = resource.getResourceMethod();
        AllowedRoles allowedRolesAnnotation = method.getAnnotation(AllowedRoles.class);
    	System.out.println(allowedRolesAnnotation);
    	System.out.println(method);
        if (allowedRolesAnnotation != null) {
        	// Get the user role from the header
            String userRole = httpHeaders.getHeaderString("X-User-Role");
            
            if (userRole == null) {
                requestContext.abortWith(Response.status(Response.Status.FORBIDDEN).entity("No role found").build());
                return;
            }
        	
            Role[] allowedRoles = allowedRolesAnnotation.value();

            // Check if the user's role is allowed for this method
            boolean allowed = false;
            for (Role role : allowedRoles) {
                if (role.equals(Role.valueOf(userRole.toUpperCase()))) {
                    allowed = true;
                    break;
                }
            }

            if (!allowed) {
                requestContext.abortWith(Response.status(Response.Status.FORBIDDEN).entity("Forbidden: Role not allowed").build());
            }
        }
    }
}
