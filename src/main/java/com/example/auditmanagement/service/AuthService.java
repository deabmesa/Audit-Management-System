package com.example.auditmanagement.service;

import com.example.auditmanagement.dto.AuthRequest;
import com.example.auditmanagement.dto.AuthResponse;
import com.example.auditmanagement.dto.RegisterRequest;

public interface AuthService {
    AuthResponse authenticate(AuthRequest request);
    void register(RegisterRequest request);
}
