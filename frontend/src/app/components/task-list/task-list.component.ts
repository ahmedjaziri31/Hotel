import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatCardModule } from '@angular/material/card';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';

@Component({
  selector: 'app-task-list',
  template: `
    <div style="padding: 20px;">
      <mat-card>
        <mat-card-header>
          <mat-card-title>Task Management</mat-card-title>
          <mat-card-subtitle>This is a placeholder for the task management component</mat-card-subtitle>
        </mat-card-header>
        <mat-card-content>
          <p>The original component has many dependencies that need to be migrated to the standalone component architecture.</p>
          <p>For now, this placeholder indicates the routing is working properly.</p>
        </mat-card-content>
        <mat-card-actions>
          <button mat-raised-button color="primary">
            <mat-icon>refresh</mat-icon> Refresh Tasks
          </button>
        </mat-card-actions>
      </mat-card>
    </div>
  `,
  standalone: true,
  imports: [
    CommonModule,
    MatCardModule,
    MatButtonModule,
    MatIconModule
  ]
})
export class TaskListComponent implements OnInit {
  constructor() { }

  ngOnInit(): void {
    console.log('TaskList component initialized');
  }
}
