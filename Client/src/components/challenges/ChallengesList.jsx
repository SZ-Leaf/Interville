import { CATEGORIES, CITIES, MOCK_CHALLENGES } from "@/mock_challenges";
import { useState } from "react";
import { Link } from "react-router";
import {
  Target,
  Trophy,
  TrendingUp,
  Zap,
  Calendar,
  Users,
  MapPin,
  Search,
  Filter,
  Clock,
} from "lucide-react";
import ChallengeListCard from "../ui/ChallengeListCard";

function ChallengesList() {
  const [selectedCategory, setSelectedCategory] = useState("Toutes");
  const [selectedCity, setSelectedCity] = useState("Toutes");
  const [statusFilter, setStatusFilter] = useState("active");

  const filteredChallenges = MOCK_CHALLENGES.filter((challenge) => {
    const categoryMatch =
      selectedCategory === "Toutes" || challenge.category === selectedCategory;
    const cityMatch =
      selectedCity === "Toutes" || challenge.city === selectedCity;
    const statusMatch =
      statusFilter === "all" || challenge.status === statusFilter;
    return categoryMatch && cityMatch && statusMatch;
  });

  const getDifficultyColor = (difficulty) => {
    switch (difficulty) {
      case "Facile":
        return "bg-tertiary/90 text-slate-700";
      case "Moyen":
        return "bg-yellow-100 text-yellow-700";
      case "Difficile":
        return "bg-red-100 text-red-700";
      default:
        return "bg-neutral-gray/10 text-neutral-gray";
    }
  };

  const getStatusBadge = (status) => {
    switch (status) {
      case "active":
        return (
          <span className="px-3 py-1 bg-green-400/70 text-green-800 rounded-full text-sm font-medium">
            Actif
          </span>
        );
      case "upcoming":
        return (
          <span className="px-3 py-1 bg-accent-light text-gray-700 rounded-full text-sm font-medium">
            À venir
          </span>
        );
      case "completed":
        return (
          <span className="px-3 py-1 bg-gray-200/40 text-gray-700 rounded-full text-sm font-medium">
            Terminé
          </span>
        );
      default:
        return null;
    }
  };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div className="mb-8">
        <h1 className="text-3xl text-dark-text mb-2 font-bold">
          Découvrir les défis
        </h1>
        <p className="text-neutral-gray">
          Rejoignez des défis et dépassez-vous
        </p>
      </div>

      {/* Stats Overview */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div className="bg-gradient-to-br from-primary to-secondary rounded-xl p-6 text-white shadow-lg shadow-primary/20">
          <div className="flex items-center justify-between mb-2">
            <Target className="w-8 h-8 opacity-80" />
            <span className="text-2xl font-bold">12</span>
          </div>
          <p className="opacity-90 font-medium">Vos défis actifs</p>
        </div>
        <div className="bg-gradient-to-br from-secondary to-tertiary rounded-xl p-6 text-white shadow-lg shadow-secondary/20">
          <div className="flex items-center justify-between mb-2">
            <Trophy className="w-8 h-8 opacity-80" />
            <span className="text-2xl font-bold">8</span>
          </div>
          <p className="opacity-90 font-medium">Terminés</p>
        </div>
        <div className="bg-gradient-to-br from-tertiary to-secondary rounded-xl p-6 text-white shadow-lg shadow-tertiary/20">
          <div className="flex items-center justify-between mb-2">
            <TrendingUp className="w-8 h-8 opacity-80" />
            <span className="text-2xl font-bold">87%</span>
          </div>
          <p className="opacity-90 font-medium">Taux de réussite</p>
        </div>
        <div className="bg-accent-light rounded-xl p-6 text-gray-700 shadow-lg shadow-accent/20">
          <div className="flex items-center justify-between mb-2">
            <Zap className="w-8 h-8 opacity-80" />
            <span className="text-2xl font-bold">45</span>
          </div>
          <p className="opacity-90 font-medium">Jours consécutifs</p>
        </div>
      </div>

      {/* Filters */}
      <div className="bg-white rounded-xl shadow-sm border border-neutral-gray/20 p-6 mb-6">
        <div className="flex flex-col gap-6">
          {/* Categories */}
          <div>
            <label className="block text-dark-text mb-2 font-medium">
              Catégories
            </label>
            <div className="flex flex-wrap gap-2">
              {CATEGORIES.map((category) => (
                <button
                  key={category}
                  onClick={() => setSelectedCategory(category)}
                  className={`px-4 py-2 rounded-lg transition-colors text-sm font-medium ${
                    selectedCategory === category
                      ? "bg-primary text-white shadow-md shadow-primary/20"
                      : "bg-light-blue/30 text-neutral-gray hover:bg-light-blue/50 hover:text-dark-text"
                  }`}
                >
                  {category}
                </button>
              ))}
            </div>
          </div>

          <div className="flex flex-col md:flex-row gap-6">
            {/* Cities */}
            <div className="flex-1">
              <label className="block text-dark-text mb-2 font-medium">
                Villes
              </label>
              <div className="relative">
                <MapPin className="absolute left-3 top-1/2 transform -translate-y-1/2 text-neutral-gray w-5 h-5 pointer-events-none" />
                <select
                  value={selectedCity}
                  onChange={(e) => setSelectedCity(e.target.value)}
                  className="w-full pl-10 pr-4 py-2 border border-neutral-gray/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent appearance-none bg-white text-dark-text"
                >
                  {CITIES.map((city) => (
                    <option key={city} value={city}>
                      {city}
                    </option>
                  ))}
                </select>
              </div>
            </div>

            {/* Status */}
            <div className="flex-1">
              <label className="block text-dark-text mb-2 font-medium">
                Statut
              </label>
              <div className="relative">
                <Filter className="absolute left-3 top-1/2 transform -translate-y-1/2 text-neutral-gray w-5 h-5 pointer-events-none" />
                <select
                  value={statusFilter}
                  onChange={(e) => setStatusFilter(e.target.value)}
                  className="w-full pl-10 pr-4 py-2 border border-neutral-gray/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent appearance-none bg-white text-dark-text"
                >
                  <option value="all">Tous les défis</option>
                  <option value="active">Actifs</option>
                  <option value="upcoming">À venir</option>
                  <option value="completed">Terminés</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
      {/* Challenges Grid */}
      <ChallengeListCard
        filteredChallenges={filteredChallenges}
        getStatusBadge={getStatusBadge}
        getDifficultyColor={getDifficultyColor}
      />

      {filteredChallenges.length === 0 && (
        <div className="text-center py-12">
          <Trophy className="w-16 h-16 text-neutral-gray/30 mx-auto mb-4" />
          <h3 className="text-xl text-dark-text mb-2 font-bold">
            Aucun défi trouvé
          </h3>
          <p className="text-neutral-gray">
            Essayez d&apos;ajuster vos filtres ou créez un nouveau défi
          </p>
        </div>
      )}
    </div>
  );
}

export default ChallengesList;
