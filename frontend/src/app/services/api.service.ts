import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) { }

  // Auth methods
  login(credentials: { email: string, password: string }): Observable<any> {
    return this.http.post(`${this.apiUrl}/login`, credentials);
  }

  logout(): Observable<any> {
    return this.http.post(`${this.apiUrl}/logout`, {});
  }

  // Room methods
  getRooms(): Observable<any> {
    return this.http.get(`${this.apiUrl}/rooms`);
  }

  getRoom(id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/rooms/${id}`);
  }

  createRoom(room: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/rooms`, room);
  }

  updateRoom(id: number, room: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/rooms/${id}`, room);
  }

  deleteRoom(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/rooms/${id}`);
  }

  // QR code methods
  getQrCodes(): Observable<any> {
    return this.http.get(`${this.apiUrl}/qr-codes`);
  }

  getQrCode(id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/qr-codes/${id}`);
  }

  getQrCodeByUniqueCode(uniqueCode: string): Observable<any> {
    return this.http.get(`${this.apiUrl}/qr-codes/${uniqueCode}`);
  }

  generateQrCode(roomId: number, data: any = {}): Observable<any> {
    return this.http.post(`${this.apiUrl}/qr-codes/generate/${roomId}`, data);
  }

  deactivateQrCode(id: number): Observable<any> {
    return this.http.put(`${this.apiUrl}/qr-codes/${id}`, { status: 'inactive' });
  }

  // Complaint methods
  getComplaints(): Observable<any> {
    return this.http.get(`${this.apiUrl}/complaints`);
  }

  getComplaint(id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/complaints/${id}`);
  }

  createComplaint(complaint: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/complaints`, complaint);
  }

  updateComplaint(id: number, complaint: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/complaints/${id}`, complaint);
  }

  // Task methods
  getTasks(params = {}): Observable<any> {
    return this.http.get(`${this.apiUrl}/tasks`, { params });
  }

  getTask(id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/tasks/${id}`);
  }

  assignTask(id: number, userId: number): Observable<any> {
    return this.http.put(`${this.apiUrl}/tasks/${id}/assign`, { assigned_to: userId });
  }

  completeTask(id: number, notes: string = ''): Observable<any> {
    return this.http.put(`${this.apiUrl}/tasks/${id}/complete`, { notes });
  }

  updateTask(id: number, task: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/tasks/${id}`, task);
  }
}
