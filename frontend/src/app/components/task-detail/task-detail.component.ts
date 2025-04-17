import { Component, Inject, OnInit } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { ApiService } from '../../services/api.service';

@Component({
  selector: 'app-task-detail',
  templateUrl: './task-detail.component.html',
  styleUrls: ['./task-detail.component.scss']
})
export class TaskDetailComponent implements OnInit {
  task: any;
  loading = true;
  error = '';

  constructor(
    private apiService: ApiService,
    public dialogRef: MatDialogRef<TaskDetailComponent>,
    @Inject(MAT_DIALOG_DATA) public data: { taskId: number }
  ) { }

  ngOnInit(): void {
    this.loadTask();
  }

  loadTask(): void {
    this.apiService.getTask(this.data.taskId)
      .subscribe(
        (data) => {
          this.task = data;
          this.loading = false;
        },
        (error) => {
          console.error('Error loading task details:', error);
          this.error = 'Unable to load task details. Please try again.';
          this.loading = false;
        }
      );
  }

  getUrgencyColor(level: string): string {
    switch (level) {
      case 'high': return 'warn';
      case 'medium': return 'accent';
      case 'low': return 'primary';
      default: return '';
    }
  }

  getStatusClass(status: string): string {
    return status.replace('_', '-');
  }

  formatStatus(status: string): string {
    return status.replace('_', ' ');
  }

  close(): void {
    this.dialogRef.close();
  }
}
