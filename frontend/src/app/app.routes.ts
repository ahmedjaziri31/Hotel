import { Routes } from '@angular/router';
import { LoginComponent } from './components/login/login.component';
import { DashboardComponent } from './components/dashboard/dashboard.component';
import { TaskListComponent } from './components/task-list/task-list.component';
import { QrGeneratorComponent } from './components/qr-generator/qr-generator.component';

// We'll use a simple auth guard for now
const simpleAuthGuard = () => {
  return true; // Always allow access for testing
};

export const routes: Routes = [
  { path: 'login', component: LoginComponent },
  {
    path: '',
    component: DashboardComponent,
    canActivate: [simpleAuthGuard],
    children: [
      { path: '', redirectTo: 'tasks', pathMatch: 'full' },
      { path: 'tasks', component: TaskListComponent },
      { path: 'qr-generator', component: QrGeneratorComponent },
    ]
  },
  { path: '**', redirectTo: '' }
];
