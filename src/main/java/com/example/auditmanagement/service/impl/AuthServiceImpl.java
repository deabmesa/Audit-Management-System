package com.example.auditmanagement.service.impl;

import com.example.auditmanagement.dto.AuthRequest;
import com.example.auditmanagement.dto.AuthResponse;
import com.example.auditmanagement.dto.RegisterRequest;
import com.example.auditmanagement.entity.oracle.Role;
import com.example.auditmanagement.entity.oracle.User;
import com.example.auditmanagement.exception.BadRequestException;
import com.example.auditmanagement.repository.oracle.RoleRepository;
import com.example.auditmanagement.repository.oracle.UserRepository;
import com.example.auditmanagement.service.AuthService;
import com.example.auditmanagement.util.JwtTokenProvider;
import lombok.RequiredArgsConstructor;
import org.springframework.security.authentication.AuthenticationManager;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.Set;

@Service
@RequiredArgsConstructor
public class AuthServiceImpl implements AuthService {

    private final AuthenticationManager authenticationManager;
    private final JwtTokenProvider jwtTokenProvider;
    private final UserRepository userRepository;
    private final RoleRepository roleRepository;
    private final PasswordEncoder passwordEncoder;

    @Override
    public AuthResponse authenticate(AuthRequest request) {
        Authentication authentication = authenticationManager.authenticate(
                new UsernamePasswordAuthenticationToken(request.getUsername(), request.getPassword()));
        String token = jwtTokenProvider.generateToken(authentication);
        return AuthResponse.builder().token(token).tokenType("Bearer").build();
    }

    @Override
    @Transactional(transactionManager = "oracleTransactionManager")
    public void register(RegisterRequest request) {
        if (userRepository.existsByUsername(request.getUsername())) {
            throw new BadRequestException("Username already exists");
        }

        Role role = roleRepository.findByName(request.getRole())
                .orElseThrow(() -> new UsernameNotFoundException("Role not configured: " + request.getRole()));

        User user = new User();
        user.setUsername(request.getUsername());
        user.setEmail(request.getEmail());
        user.setPassword(passwordEncoder.encode(request.getPassword()));
        user.setRoles(Set.of(role));

        userRepository.save(user);
    }
}
