import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:taskflut/api/api_service.dart';
import 'package:taskflut/api/auth_service.dart';
import 'package:taskflut/providers/task_provider.dart';
import 'package:taskflut/screens/auth/login_screen.dart';
import 'package:taskflut/screens/home_screen.dart';
import 'package:taskflut/screens/splash_screen.dart';
import 'package:taskflut/utils/shared_prefs.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await SharedPrefs.init();
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        // Auth Provider
        ChangeNotifierProvider(create: (_) => AuthService()),
        
        // Task Provider (ditambahkan)
        ChangeNotifierProvider(
          create: (context) => TaskProvider(
            authService: context.read<AuthService>(),
            apiService: ApiService(),
          )..loadTasks(), // Auto-load tasks on startup
        ),
        
        // Add other providers here if needed
      ],
      child: MaterialApp(
        title: 'TaskFlut',
        debugShowCheckedModeBanner: false,
        theme: ThemeData(
          primarySwatch: Colors.red,
          scaffoldBackgroundColor: Colors.white,
          appBarTheme: const AppBarTheme(
            backgroundColor: Colors.black,
            foregroundColor: Colors.white,
            elevation: 0,
          ),
          inputDecorationTheme: InputDecorationTheme(
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(10),
              borderSide: const BorderSide(color: Colors.black),
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(10),
              borderSide: const BorderSide(color: Colors.red),
            ),
          ),
        ),
        home: const SplashScreen(),
        routes: {
          '/login': (context) => const LoginScreen(),
          '/home': (context) => const HomeScreen(),
        },
        // Error handling for routes
        onUnknownRoute: (settings) => MaterialPageRoute(
          builder: (_) => Scaffold(
            body: Center(
              child: Text('No route defined for ${settings.name}'),
            ),
          ),
        ),
      ),
    );
  }
}