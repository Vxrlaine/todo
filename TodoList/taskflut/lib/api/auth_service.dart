import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:taskflut/models/user.dart';
import 'package:taskflut/utils/constants.dart';
import 'package:taskflut/utils/shared_prefs.dart';

class AuthService with ChangeNotifier {
  String? _token;
  User? _user;

  String? get token => _token;
  User? get user => _user;

  Future<void> login(String email, String password) async {
  try {
    final response = await http.post(
      Uri.parse(ApiConstants.login),
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: json.encode({
        'email': email,
        'password': password,
        'device_name': 'flutter_device',
      }),
    );

    final responseData = json.decode(response.body);
    
    if (response.statusCode == 200) {
      _token = responseData['token'];
      _user = User.fromJson(responseData['user']);
      
      await SharedPrefs.setString(AppConstants.tokenKey, _token!);
      await SharedPrefs.setString(
        AppConstants.userKey, 
        json.encode(_user!.toJson())
      );
      
      notifyListeners();
    } else {
      throw responseData['message'] ?? 'Login failed with status ${response.statusCode}';
    }
  } catch (e) {
    throw 'Failed to connect to the server. Please check your internet connection.';
  }
}

  Future<void> register(String name, String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse(ApiConstants.register),
        headers: {'Accept': 'application/json'},
        body: {
          'name': name,
          'email': email,
          'password': password,
          'password_confirmation': password,
          'device_name': 'flutter_device',
        },
      );

      final responseData = json.decode(response.body);
      if (response.statusCode == 200) {
        _token = responseData['token'];
        _user = User.fromJson(responseData['user']);
        
        await SharedPrefs.setString(AppConstants.tokenKey, _token!);
        await SharedPrefs.setString(
          AppConstants.userKey, 
          json.encode(_user!.toJson())
        );
        
        notifyListeners();
      } else {
        throw responseData['message'] ?? 'Registration failed';
      }
    } catch (e) {
      rethrow;
    }
  }

  Future<void> logout() async {
    try {
      final token = await SharedPrefs.getString(AppConstants.tokenKey);
      final response = await http.post(
        Uri.parse(ApiConstants.logout),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        _token = null;
        _user = null;
        await SharedPrefs.clear();
        notifyListeners();
      } else {
        throw 'Logout failed';
      }
    } catch (e) {
      rethrow;
    }
  }

  Future<bool> autoLogin() async {
    final token = await SharedPrefs.getString(AppConstants.tokenKey);
    final userData = await SharedPrefs.getString(AppConstants.userKey);

    if (token != null && userData != null) {
      _token = token;
      _user = User.fromJson(json.decode(userData));
      notifyListeners();
      return true;
    }
    return false;
  }
}