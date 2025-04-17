import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatCardModule } from '@angular/material/card';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';

@Component({
  selector: 'app-qr-generator',
  template: `
    <div style="padding: 20px;">
      <mat-card>
        <mat-card-header>
          <mat-card-title>QR Code Generator</mat-card-title>
          <mat-card-subtitle>This is a placeholder for the QR code generator component</mat-card-subtitle>
        </mat-card-header>
        <mat-card-content>
          <p>The original component requires Angular Material and QR code library integration.</p>
          <p>For now, this placeholder indicates the routing to this component is working properly.</p>
        </mat-card-content>
        <mat-card-actions>
          <button mat-raised-button color="primary">
            <mat-icon>qr_code</mat-icon> Generate QR Code
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
export class QrGeneratorComponent {
  constructor() { }
}
