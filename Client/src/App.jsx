import "@/App.css";
import { Routes, Route, Navigate } from "react-router";
import LoginPage from "@/components/auth/LoginPage";
import SignupPage from "@/components/auth/SignupPage";
import ChallengesList from "@/components/challenges/ChallengesList";
import Admin from "@/components/admin/Admin";
import Profile from "@/components/auth/Profile";
import { AuthProvider } from "@/contexts/AuthContext";
import PrivateRoute from "@/components/auth/PrivateRoute";
import PublicRoute from "@/components/auth/PublicRoute";

function App() {
  return (
    <AuthProvider>
      <div className="min-h-screen bg-light-blue">
        <Routes>
          {/* 
            Public Routes:
            Accessible only when the user is NOT logged in.
            Useful for authentication pages.
          */}
          <Route element={<PublicRoute />}>
            <Route path="/login" element={<LoginPage />} />
            <Route path="/signup" element={<SignupPage />} />
          </Route>

          {/* 
            Protected Routes:
            Accessible only when the user IS logged in.
            Redirects to /login if unauthenticated.
          */}
          <Route element={<PrivateRoute />}>
            <Route path="/challenges" element={<ChallengesList />} />
            <Route path="/challenges/:id" element={<ChallengesList />} />
            <Route path="/create-challenges" element={<ChallengesList />} />
            <Route path="/profile" element={<Profile />} />
            <Route path="/" element={<Navigate to="/challenges" replace />} />
          </Route>

          {/* 
            Admin Routes:
            Accessible only when logged in AND has 'ROLE_ADMIN'.
            Redirects to home if unauthorized.
          */}
          <Route element={<PrivateRoute roles={["ROLE_ADMIN"]} />}>
            <Route path="/admin" element={<Admin />} />
          </Route>

          {/* Fallback Route: Catch-all for undefined paths */}
          <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
      </div>
    </AuthProvider>
  );
}

export default App;
