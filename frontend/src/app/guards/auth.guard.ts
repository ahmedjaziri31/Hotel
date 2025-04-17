import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, Router } from '@angular/router';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanActivate {

  constructor(
    private authService: AuthService,
    private router: Router
  ) { }

  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean {
    if (this.authService.isLoggedIn()) {
      // Check if route has role restriction
      const requiredRoles = route.data['roles'] as Array<string>;
      if (requiredRoles) {
        // Check if user has the required role
        const hasRole = this.authService.hasRole(requiredRoles);
        if (!hasRole) {
          // Redirect to tasks page if user doesn't have the required role
          this.router.navigate(['/tasks']);
          return false;
        }
      }
      return true;
    }

    // Not logged in, redirect to login page
    this.router.navigate(['/login'], { queryParams: { returnUrl: state.url } });
    return false;
  }
}
