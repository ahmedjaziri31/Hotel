import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { MatSnackBar } from '@angular/material/snack-bar';
import { ApiService } from '../../services/api.service';

@Component({
  selector: 'app-complaint-form',
  templateUrl: './complaint-form.component.html',
  styleUrls: ['./complaint-form.component.scss']
})
export class ComplaintFormComponent implements OnInit {
  complaintForm: FormGroup;
  loading = false;
  qrCode: any = null;
  roomDetails: any = null;
  invalidQr = false;
  submitted = false;
  uniqueCode: string = '';

  constructor(
    private formBuilder: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private apiService: ApiService,
    private snackBar: MatSnackBar
  ) {
    this.complaintForm = this.formBuilder.group({
      clientName: ['', [Validators.required, Validators.maxLength(100)]],
      description: ['', [Validators.required, Validators.minLength(10)]],
      urgencyLevel: ['medium', Validators.required]
    });
  }

  ngOnInit(): void {
    this.route.params.subscribe(params => {
      if (params['code']) {
        this.uniqueCode = params['code'];
        this.getQrCodeDetails(this.uniqueCode);
      }
    });
  }

  getQrCodeDetails(code: string): void {
    this.loading = true;
    this.apiService.getQrCodeByUniqueCode(code)
      .subscribe(
        (data) => {
          this.qrCode = data;
          this.roomDetails = data.room;
          this.loading = false;
        },
        (error) => {
          console.error('Error loading QR code details:', error);
          this.invalidQr = true;
          this.loading = false;

          // Show error message
          let errorMsg = 'Invalid QR code.';
          if (error.status === 403) {
            errorMsg = 'This QR code has expired. Please contact hotel staff.';
          } else if (error.status === 404) {
            errorMsg = 'QR code not found or inactive. Please contact hotel staff.';
          }

          this.snackBar.open(errorMsg, 'Close', {
            duration: 5000
          });
        }
      );
  }

  onSubmit(): void {
    if (this.complaintForm.invalid || !this.qrCode) {
      return;
    }

    this.loading = true;
    const complaintData = {
      qr_code_id: this.qrCode.id,
      client_name: this.complaintForm.value.clientName,
      description: this.complaintForm.value.description,
      urgency_level: this.complaintForm.value.urgencyLevel
    };

    this.apiService.createComplaint(complaintData)
      .subscribe(
        (response) => {
          this.loading = false;
          this.submitted = true;
          this.snackBar.open('Your complaint has been submitted successfully!', 'Close', {
            duration: 5000
          });
        },
        (error) => {
          console.error('Error submitting complaint:', error);
          this.loading = false;
          this.snackBar.open('Error submitting complaint. Please try again.', 'Close', {
            duration: 5000
          });
        }
      );
  }

  resetForm(): void {
    this.complaintForm.reset({
      urgencyLevel: 'medium'
    });
  }
}
