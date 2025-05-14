/**
 * 
 */
package rest.filters;

import static java.lang.annotation.ElementType.METHOD;
import static java.lang.annotation.RetentionPolicy.RUNTIME;

import java.lang.annotation.Retention;
import java.lang.annotation.Target;

import models.Role;

@Retention(RUNTIME)
@Target(METHOD)
/**
 * @author Tony
 *
 */
public @interface AllowedRoles {
	Role[] value();
}
