class ApiConstants {
  static const String baseUrl = 'http://localhost:8000/api';
  static const String login = '$baseUrl/auth/login';
  static const String register = '$baseUrl/auth/register';
  static const String logout = '$baseUrl/auth/logout';
  static const String user = '$baseUrl/auth/user';
  static const String tasks = '$baseUrl/tasks';
}

class AppConstants {
  static const String appName = 'TaskFlut';
  static const String tokenKey = 'auth_token';
  static const String userKey = 'user_data';
}