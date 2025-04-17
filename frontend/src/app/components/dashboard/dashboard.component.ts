import { Component, OnInit } from '@angular/core';
import { Router, RouterModule } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { CommonModule } from '@angular/common';
import { MatToolbarModule } from '@angular/material/toolbar';
import { MatSidenavModule } from '@angular/material/sidenav';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { MatListModule } from '@angular/material/list';
import { MatMenuModule } from '@angular/material/menu';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss'],
  standalone: true,
  imports: [
    CommonModule,
    RouterModule,
    MatToolbarModule,
    MatSidenavModule,
    MatButtonModule,
    MatIconModule,
    MatListModule,
    MatMenuModule
  ]
})
export class DashboardComponent implements OnInit {
  currentUser: any;
  sidenavOpen = true;

  constructor(
    private authService: AuthService,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.authService.currentUser.subscribe(user => {
      this.currentUser = user || { name: 'Test User', role: 'admin' }; // Fallback for testing
    });
  }

  logout(): void {
    // For testing, just navigate to login
    this.router.navigate(['/login']);
    // this.authService.logout();
  }

  toggleSidenav(): void {
    this.sidenavOpen = !this.sidenavOpen;
  }

  canAccessQrGenerator(): boolean {
    // Only admin, receptionist, and maintenance chief can generate QR codes
    if (!this.currentUser) return false;
    return ['admin', 'receptionist', 'maintenance_chief'].includes(this.currentUser.role);
  }
}
