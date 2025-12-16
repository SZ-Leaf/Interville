import { useState, useEffect, useCallback } from "react";
import { Link, useParams } from "react-router";
import {
  Calendar,
  Users,
  Trophy,
  ArrowLeft,
  Heart,
  MessageCircle,
  Share2,
  CheckCircle,
  AlertCircle,
} from "lucide-react";
import { useAuth } from "@/hooks/useAuth";
import * as challengeService from "@/service/challengeService";

function ChallengeDetail() {
  const { id } = useParams();
  const { user } = useAuth();

  const [challenge, setChallenge] = useState(null);
  const [comments, setComments] = useState([]);
  const [newComment, setNewComment] = useState("");
  const [isJoined, setIsJoined] = useState(false);
  const [participation, setParticipation] = useState(null);
  const [isLoading, setIsLoading] = useState(true);
  const [isJoining, setIsJoining] = useState(false);
  const [error, setError] = useState("");

  const fetchChallengeDetails = useCallback(async () => {
    setIsLoading(true);
    setError("");

    try {
      const data = await challengeService.getChallengeById(id);
      setChallenge(data);
    } catch (err) {
      setError("Échec du chargement du défi. Veuillez réessayer.");
      console.error("Erreur lors du chargement du défi :", err);
    } finally {
      setIsLoading(false);
    }
  }, [id]);

  const fetchComments = useCallback(async () => {
    try {
      const data = await challengeService.getChallengeComments(id);
      setComments(data);
    } catch (err) {
      console.error("Erreur lors du chargement des commentaires :", err);
    }
  }, [id]);

  const checkUserParticipation = useCallback(async () => {
    try {
      const data = await challengeService.getUserParticipation(id);
      if (data) {
        setIsJoined(true);
        setParticipation(data);
      }
    } catch (err) {
      // User is not participating - this is not an error
      console.error("L'utilisateur ne participe pas encore au défi. : ", err);
    }
  }, [id]);

  useEffect(() => {
    if (id) {
      fetchChallengeDetails();
      fetchComments();
      checkUserParticipation();
    }
  }, [id, fetchChallengeDetails, fetchComments, checkUserParticipation]);

  const handleJoin = async () => {
    setIsJoining(true);
    setError("");

    try {
      const data = await challengeService.joinChallenge(id);
      setIsJoined(true);
      setParticipation(data);
      // Refresh challenge data to update participant count
      fetchChallengeDetails();
    } catch (err) {
      setError("Échec de l'inscription au défi. Veuillez réessayer.");
      console.error("Erreur lors de l'inscription au défi :", err);
    } finally {
      setIsJoining(false);
    }
  };

  const handleAddComment = async (e) => {
    e.preventDefault();
    if (!newComment.trim()) return;

    try {
      const comment = await challengeService.addChallengeComment(id, {
        content: newComment,
      });
      setComments([comment, ...comments]);
      setNewComment("");
    } catch (err) {
      console.error("Error adding comment:", err);
      alert("Échec de l'ajout du commentaire. Veuillez réessayer.");
    }
  };

  if (isLoading) {
    return (
      <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div className="text-center py-12">
          <div className="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
          <p className="mt-4 text-neutral-gray">Chargement du défi...</p>
        </div>
      </div>
    );
  }

  if (error || !challenge) {
    return (
      <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div className="text-center py-12">
          <AlertCircle className="w-16 h-16 text-red-500 mx-auto mb-4" />
          <h3 className="text-xl text-gray-900 mb-2">Erreur</h3>
          <p className="text-gray-600 mb-4">{error || "Défi introuvable"}</p>
          <Link
            to="/challenges"
            className="inline-block px-6 py-3 bg-primary text-white rounded-lg hover:opacity-90"
          >
            Retour aux défis
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <Link
        to="/challenges"
        className="inline-flex items-center gap-2 text-blue-600 hover:opacity-80 mb-6"
      >
        <ArrowLeft className="w-4 h-4" />
        Retour aux défis
      </Link>

      {/* Challenge Header */}
      <div className="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div className="h-64 bg-gradient-to-br from-[#458beb] to-[#7baecc] relative">
          <div className="absolute inset-0 bg-black/20" />
          <div className="absolute bottom-6 left-6 right-6">
            <div className="flex items-center gap-2 mb-3">
              <span className="px-3 py-1 bg-white/90 text-blue-600 rounded-full text-sm">
                {challenge.category || challenge.theme}
              </span>
              <span className="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm">
                {challenge.difficulty}
              </span>
            </div>
            <h1 className="text-4xl text-white mb-2">{challenge.title}</h1>
            <p className="text-white/90 text-lg">{challenge.description}</p>
          </div>
        </div>

        <div className="p-6">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <Calendar className="w-6 h-6 text-blue-600" />
              </div>
              <div>
                <p className="text-sm text-gray-500">Durée</p>
                <p className="text-gray-900">
                  {new Date(challenge.startDate).toLocaleDateString("fr-FR")} -{" "}
                  <br />
                  {new Date(challenge.endDate).toLocaleDateString("fr-FR")}
                </p>
              </div>
            </div>
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-sky-200 rounded-lg flex items-center justify-center">
                <Users className="w-6 h-6 text-sky-600" />
              </div>
              <div>
                <p className="text-sm text-gray-500">Participants</p>
                <p className="text-gray-900">
                  {challenge.participants || 0} inscrits
                </p>
              </div>
            </div>
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <Trophy className="w-6 h-6 text-green-600" />
              </div>
              <div>
                <p className="text-sm text-gray-500">Créateur</p>
                <p className="text-gray-900">{challenge.creator || "Admin"}</p>
              </div>
            </div>
          </div>

          {error && (
            <div className="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
              <p className="text-red-800">{error}</p>
            </div>
          )}

          <div className="flex gap-3">
            {!isJoined ? (
              <button
                onClick={handleJoin}
                disabled={isJoining}
                className="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:opacity-90 transition-opacity disabled:opacity-50"
              >
                {isJoining ? "Inscription..." : "Rejoindre le défi"}
              </button>
            ) : (
              <button className="flex-1 bg-green-600 text-white py-3 rounded-lg flex items-center justify-center gap-2">
                <CheckCircle className="w-5 h-5" />
                Inscrit
              </button>
            )}
            <button className="px-6 py-3 rounded-lg hover:bg-gray-100 transition-colors">
              <Heart className="w-5 h-5" />
            </button>
            <button className="px-6 py-3 rounded-lg hover:bg-gray-100 transition-colors">
              <Share2 className="w-5 h-5" />
            </button>
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Main Content */}
        <div className="lg:col-span-2 space-y-6">
          {/* About */}
          <div className="bg-white rounded-xl shadow-sm p-6">
            <h2 className="text-2xl text-gray-900 mb-4">À propos de ce défi</h2>
            <p className="text-gray-600 mb-4">
              {challenge.fullDescription || challenge.description}
            </p>

            {challenge.rules && challenge.rules.length > 0 && (
              <>
                <h3 className="text-xl text-gray-900 mb-3">Règles du défi</h3>
                <ul className="space-y-2">
                  {challenge.rules.map((rule, index) => (
                    <li key={index} className="flex items-start gap-2">
                      <CheckCircle className="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" />
                      <span className="text-gray-600">{rule}</span>
                    </li>
                  ))}
                </ul>
              </>
            )}
          </div>

          {/* Progress */}
          {isJoined && participation && (
            <div className="bg-white rounded-xl shadow-sm p-6">
              <h2 className="text-2xl text-gray-900 mb-4">Votre progression</h2>
              <div className="mb-4">
                <div className="flex justify-between mb-2">
                  <span className="text-gray-600">Progression</span>
                  <span className="text-gray-900 font-medium">
                    {participation.progress || 0}%
                  </span>
                </div>
                <div className="w-full bg-gray-200 rounded-full h-3">
                  <div
                    className="bg-gradient-to-r from-blue-600 to-sky-600 h-3 rounded-full transition-all"
                    style={{ width: `${participation.progress || 0}%` }}
                  />
                </div>
              </div>
            </div>
          )}

          {/* Comments Section */}
          <div className="bg-white rounded-xl shadow-sm p-6">
            <h2 className="text-2xl text-gray-900 mb-4 flex items-center gap-2">
              <MessageCircle className="w-6 h-6" />
              Commentaires ({comments.length})
            </h2>

            {/* Add Comment */}
            <form onSubmit={handleAddComment} className="mb-6">
              <div className="flex items-start gap-3">
                <img
                  src={
                    user?.avatar ||
                    `https://api.dicebear.com/7.x/avataaars/svg?seed=${user?.email}`
                  }
                  alt={user?.username}
                  className="w-10 h-10 rounded-full"
                />
                <div className="flex-1">
                  <textarea
                    value={newComment}
                    onChange={(e) => setNewComment(e.target.value)}
                    placeholder="Ajouter un commentaire..."
                    className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent resize-none"
                    rows={3}
                  />
                  <button
                    type="submit"
                    className="mt-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:opacity-90 transition-opacity"
                  >
                    Publier
                  </button>
                </div>
              </div>
            </form>

            {/* Comments List */}
            <div className="space-y-4">
              {comments.map((comment) => (
                <div key={comment.id} className="flex items-start gap-3">
                  <img
                    src={comment.avatar || comment.user?.avatar}
                    alt={comment.user?.username || "User"}
                    className="w-10 h-10 rounded-full"
                  />
                  <div className="flex-1">
                    <div className="bg-gray-50 rounded-lg p-4">
                      <div className="flex items-center gap-2 mb-1">
                        <span className="font-medium text-gray-900">
                          {comment.user?.username || "User"}
                        </span>
                        <span className="text-sm text-gray-500">
                          {comment.timestamp ||
                            new Date(comment.createdAt).toLocaleDateString(
                              "fr-FR"
                            )}
                        </span>
                      </div>
                      <p className="text-gray-600">{comment.content}</p>
                    </div>
                    <button className="mt-2 text-sm text-gray-500 hover:text-blue-600 flex items-center gap-1">
                      <Heart className="w-4 h-4" />
                      {comment.likes || 0}
                    </button>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Sidebar */}
        <div className="space-y-6">
          {/* Leaderboard */}
          <div className="bg-white rounded-xl shadow-sm p-6">
            <h3 className="text-xl text-gray-900 mb-4">Classement</h3>
            <div className="space-y-3">
              <div className="flex items-center gap-3 p-3 bg-yellow-50 rounded-lg">
                <div className="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold">
                  1
                </div>
                <div className="flex-1">
                  <p className="font-medium text-gray-900">Coming soon...</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default ChallengeDetail;
