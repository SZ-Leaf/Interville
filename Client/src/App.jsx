import "@/App.css";
import { Routes, Route, Navigate } from "react-router";
import LoginPage from "@/pages/auth/LoginPage";
import SignupPage from "@/pages/auth/SignupPage";
import ChallengesList from "@/pages/challenges/ChallengesList";
import Profile from "@/pages/profile/Profile";
import { AuthProvider } from "@/contexts/AuthContext";
import PrivateRoute from "@/components/auth/PrivateRoute";
import PublicRoute from "@/components/auth/PublicRoute";
import ChallengeDetail from "@/pages/challenges/ChallengeDetail";
import Navigation from "@/components/layout/Navigations";
import AdminDashboard from "@/pages/admin/AdminDashboard";
import CreateChallenge from "@/pages/challenges/CreateChallenge";

function App() {
  return (
    <AuthProvider>
      <div className="min-h-screen bg-light-blue">
        <Navigation />
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
            <Route path="/challenges/:id" element={<ChallengeDetail />} />
            <Route path="/create-challenge" element={<CreateChallenge />} />
            <Route path="/profile" element={<Profile />} />
            <Route path="/" element={<Navigate to="/challenges" replace />} />
          </Route>
          {/*
              Admin Routes:
              Accessible only when logged in AND has 'ROLE_ADMIN'.
              Redirects to home if unauthorized.
            */}
          <Route element={<PrivateRoute roles={["ROLE_ADMIN"]} />}>
            <Route path="/admin" element={<AdminDashboard />} />
          </Route>
          {/* Fallback Route: Catch-all for undefined paths */}
          <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
      </div>
    </AuthProvider>
  );
}

export default App;
