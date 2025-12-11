import { Navigate, Outlet } from "react-router";
import { useAuth } from "@/hooks/useAuth";

/**
 * PublicRoute Component
 *
 * Restricts access to routes that should only be visible to unauthenticated users
 * (e.g., Login, Signup). Redirects logged-in users to the dashboard.
 */
const PublicRoute = () => {
  const { user } = useAuth();

  if (user) {
    return <Navigate to="/challenges" replace />;
  }

  return <Outlet />;
};

export default PublicRoute;
